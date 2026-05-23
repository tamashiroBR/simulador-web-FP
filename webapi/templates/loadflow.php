<?php
// PHP 8 compatible: uses sequential LoadFlow instead of LoadFlowT (which requires pthreads).
// NOTE: 'use' declarations are not allowed inside method scope (Slim renders templates via
// require inside View::render()). Use the fully-qualified class name instead.
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../bootstrap.php';

    if (!is_null($data)) {

        // json_decode returns stdClass objects; LoadFlow expects plain indexed PHP arrays.
        // Convert recursively: stdClass → array, nested arrays preserved.
        $toArray = function ($value) use (&$toArray) {
            if (is_object($value)) {
                $value = (array) $value;
            }
            if (is_array($value)) {
                return array_map($toArray, array_values($value));
            }
            return $value;
        };

        $dataArr = [
            'optLF'  => $toArray($data['optLF']),
            'bus'    => $toArray($data['bus']),
            'branch' => $toArray($data['branch']),
        ];

        $lf = new \NDSE\Tools\LoadFlow($dataArr);
        $lf->makeYbus();
        $result = $lf->run();

        if (empty($result)) {
            echo json_encode(['iteration' => null, 'bus' => null, 'branch' => null, 'loss' => null]);
        } else {
            echo $result;
        }
    }

} catch (\Throwable $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'file'  => basename($e->getFile()),
        'line'  => $e->getLine()
    ]);
}

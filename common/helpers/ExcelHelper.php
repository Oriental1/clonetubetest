<?php

namespace common\helpers;

class ExcelHelper
{
    public static function trimExcelData($data)
    {
        // Remove rows where only one cell has data
        $filteredData = array_filter($data, function ($row) {
            $nonEmptyCells = array_filter($row, fn($cell) => trim($cell) !== '');
            return count($nonEmptyCells) > 1; // Keep only rows with more than one non-empty cell
        });

        // Remove empty columns
        if (!empty($filteredData)) {
            $columnCount = max(array_map('count', $filteredData)); // Get max column count
            $nonEmptyColumns = [];

            // Check each column index if it contains more than one non-empty value
            for ($colIndex = 0; $colIndex < $columnCount; $colIndex++) {
                $nonEmptyCells = 0;
                foreach ($filteredData as $row) {
                    if (isset($row[$colIndex]) && trim($row[$colIndex]) !== '') {
                        $nonEmptyCells++;
                    }
                }
                if ($nonEmptyCells > 1) { // Keep only columns with more than one non-empty cell
                    $nonEmptyColumns[] = $colIndex;
                }
            }

            // Rebuild the table with only non-empty columns
            $finalData = [];
            foreach ($filteredData as $row) {
                $newRow = [];
                foreach ($nonEmptyColumns as $colIndex) {
                    $newRow[] = isset($row[$colIndex]) && trim($row[$colIndex]) !== '' ? trim($row[$colIndex]) : 'NULL';
                }
                $finalData[] = $newRow;
            }
        } else {
            $finalData = [];
        }

        return $finalData;
    }
}

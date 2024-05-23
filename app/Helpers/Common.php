<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class Common
{
    /**
     * Generate a token hash based on the given parameters.
     *
     * @param String $params
     * @return md5
     */
    public static function createTokenHash($email, $created_at)
    {
        $token_hash = '';
        $params = array();

        unset($params['token_hash']);
        // $token_hash = implode($params) . config('app.str_uuid_hash');
        $token_hash = $email . $created_at . config('app.str_uuid_hash');
        $token_hash = md5($token_hash);

        return $token_hash;
    }


    /**
     * Validate an uploaded file as an Excel file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file The uploaded file to validate.
     * @return void
     */
    public static function validateExcelFile(UploadedFile $file) {
        $validator = Validator::make(
            ['file' => $file],
            ['file' => 'required|file|mimes:xls,xlsx'],
            [
                'file.required' => 'Please select an Excel file.',
                'file.file' => 'The selected file is invalid.',
                'file.mimes' => 'The selected file must be an Excel file.',
            ]
        );

        return $validator->validate();
    }


    /**
     * Convert coordinate string to decimal format.
     *
     * @param string $inputString The input coordinate string.
     * @return float|null The decimal representation of the coordinate, or null if the input is invalid.
     */
    public static function convertCoordinatesToDecimal($inputString) {
        // Define the regular expression pattern to handle different input formats.
        $pattern = "/(\d+)°(?:([\d.]+)(?:'|′))?((?:[\d.]+)(?:''|\"|″))?\s*([NSEW])/";

        // Perform the regular expression match.
        if (!preg_match($pattern, $inputString, $matches)) {
            return null;
        }

        // Extract degrees, minutes, seconds, and direction from the matched values.
        $degrees = intval($matches[1]);
        $minutes = isset($matches[2]) ? floatval($matches[2]) : 0;
        $seconds = isset($matches[3]) ? floatval($matches[3]) : 0;
        $direction = strtoupper($matches[4]);

        // Calculate the decimal value.
        $decimalValue = $degrees + ($minutes / 60) + ($seconds / 3600);

        // Handle negative values for West and South directions.
        if ($direction === 'W' || $direction === 'S') {
            $decimalValue = -$decimalValue;
        }

        // Return the decimal value as a string.
        return round($decimalValue, 6);
    }


    /**
     * Parse the input date, converting Excel serial numbers to dates by default.
     * Returns null for invalid dates.
     *
     * @param mixed $date The input date to parse (can be numeric or string).
     * @return string|null The parsed date in "Y-m-d" format, or null for invalid dates.
     */
    public static function parseDate($date)
    {
        if (is_numeric($date)) {
            try {
                // Convert Excel serial number to date.
                $parsedDate = \Carbon\Carbon::createFromDate(1900, 1, 1)
                    // Subtract 2 instead of 1 to handle the Excel leap year bug.
                    ->addDays((int)$date - 2) 
                    ->toDateString();

                return $parsedDate;
            } catch (\Exception $e) {
                // Return null for invalid dates.
                return null; 
            }
        }

        try {
            // If the input is a string in a specific date format (e.g., "YYYY-DD-MM" or "MM/DD/YYYY"),
            // parse the input date string.
            $parsedDate = \Carbon\Carbon::parse($date);
            return $parsedDate->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     ** WORK IN PROGRESS
     * Split the options string into separate options and their meanings.
     *
     * @param string $options The options string to split.
     * @return array An array containing the options and their meanings.
     */
    public static function splitOptions(string $options): array {
        // Split options if it finds "),".
        $optionsArray = explode('),', $options);

        // Trim each option to remove any extra spaces before or after it.
        // $trimmedOptions = array_map('trim', $optionsArray);

        $result = [];

        foreach ($optionsArray as $option) {
            // Remove trailing comma if exists.
            $option = rtrim($option, ','); 

            // Split option into parts.
            $optionParts = explode('(', $option, 2); 

            // Extract the option text.
            $optionText = trim($optionParts[0]); 

            // Extract the meaning.
            $meaning = isset($optionParts[1]) ? trim($optionParts[1]) : ''; 

            $result[] = $optionText . ' (' . $meaning . ')';
        }

        return $result;
    }


    /**
     ** WORK IN PROGRESS
     * Add records for selected options in checkboxes.
     *
     * @param string $options          The string containing options separated by commas.
     * @param string $modelName        The fully qualified name of the model to create records with.
     * @param mixed  $formId           The ID of the form associated with the records.
     * @param mixed  $propertyId       The ID of the property associated with the records.
     *
     * @return void
     */
    public static function addCheckboxOptions(string $options, string $modelName, $formId, $propertyId): void {
        $selectedOptions = self::splitOptions($options);

        foreach ($selectedOptions as $option) {
            $model = new $modelName();
            $model->create([
                'name' => $option,
                'form_id' => $formId,
                'property_id' => $propertyId,
            ]);
        }
    }
}
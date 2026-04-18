<?php

function validate($data, $rules) {

    $errors = [];

    foreach ($rules as $field => $ruleSet) {

        $value = $data[$field] ?? '';

        foreach ($ruleSet as $rule) {

            if ($rule === "required" && empty($value)) {
                $errors[$field][] = "Field is required";
            }

            if ($rule === "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "Invalid email";
            }

            if (strpos($rule, "min:") === 0) {
                $min = explode(":", $rule)[1];
                if (strlen($value) < $min) {
                    $errors[$field][] = "Minimum length is $min";
                }
            }
        }
    }

    return $errors;
}
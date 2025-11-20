<?php

declare(strict_types=1);

namespace BridgeBoard\Services;

final class Validation
{
    public static function make(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesList = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesList as $rule) {
                $rule = trim($rule);
                $params = null;

                if (str_contains($rule, ':')) {
                    [$rule, $params] = explode(':', $rule, 2);
                }

                switch ($rule) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field][] = 'This field is required.';
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = 'Invalid email address.';
                        }
                        break;
                    case 'min':
                        if ($value && strlen((string) $value) < (int) $params) {
                            $errors[$field][] = "Must be at least {$params} characters.";
                        }
                        break;
                    case 'max':
                        if ($value && strlen((string) $value) > (int) $params) {
                            $errors[$field][] = "Must be less than {$params} characters.";
                        }
                        break;
                    case 'numeric':
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $errors[$field][] = 'Must be a number.';
                        }
                        break;
                    case 'confirmed':
                        $confirmationField = $field . '_confirmation';
                        if (($data[$confirmationField] ?? null) !== $value) {
                            $errors[$field][] = 'Confirmation does not match.';
                        }
                        break;
                }
            }
        }

        return $errors;
    }
}

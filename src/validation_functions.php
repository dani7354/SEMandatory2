<?php

  function has_length_greater_than($value, $min) {
    $length = strlen($value);
    return $length > $min;
  }
  function has_length_less_than($value, $max) {
    $length = strlen($value);
    return $length < $max;
  }
  function has_length_exactly($value, $exact) {
    $length = strlen($value);
    return $length == $exact;
  }
  function has_valid_email_format($value) {
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
    return preg_match($email_regex, $value) === 1;
  }
?>

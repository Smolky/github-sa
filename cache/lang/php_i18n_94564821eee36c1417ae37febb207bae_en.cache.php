<?php class I {
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
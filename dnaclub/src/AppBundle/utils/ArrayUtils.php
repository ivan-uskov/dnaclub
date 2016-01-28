<?

namespace AppBundle\utils;

class ArrayUtils
{
    public static function getParameter($array, $parameterName, $default = null)
    {
        return isset($array[$parameterName]) ? $array[$parameterName] : $default;
    }

    public static function toJson(array $data)
    {
        return json_encode($data);
    }

    public static function fromJson($data)
    {
        return json_decode($data, true);
    }
}
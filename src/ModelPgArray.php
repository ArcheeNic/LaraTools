<?php
namespace ArcheeNic\LaraTools;

/**
 * @deprecated
 */
trait ModelPgArray
{
    /**
     * @param  array  $set
     *
     * @return string
     * @see https://stackoverflow.com/a/35175284
     */
    public function pgArrayMake($set): string
    {
        $set    = (array)$set; // can be called with a scalar or array
        $result = [];
        foreach ($set as $t) {
            if (is_array($t)) {
                $result[] = $this->pgArrayMake($t);
            } else {
                $t = str_replace('"', '\\"', $t); // escape double quote
                if (!is_numeric($t)) // quote only non-numeric values
                {
                    $t = '"' . $t . '"';
                }
                $result[] = $t;
            }
        }

        return '{' . implode(",", $result) . '}'; // format
    }

    /**
     * @param  string  $s
     * @param  int     $start
     * @param  null    $end
     *
     * @return array|null
     * @see https://stackoverflow.com/a/27964420
     */
    public function pgArrayParse($s, int $start = 0, &$end = null): ?array
    {
        if (empty($s) || $s[0] !== '{') {
            return null;
        }
        $return = [];
        $string = false;
        $quote  = '';
        $len    = strlen($s);
        $v      = '';
        for ($i = $start + 1; $i < $len; $i++) {
            $ch = $s[$i];

            if (!$string && $ch === '}') {
                if ($v !== '' || !empty($return)) {
                    $return[] = $v;
                }
                $end = $i;
                break;
            }

            if (!$string && $ch === '{') {
                $v = $this->pgArrayParse($s, $i, $i);
            } elseif (!$string && $ch === ',') {
                $return[] = $v;
                $v        = '';
            } elseif (!$string && ($ch === '"' || $ch === "'")) {
                $string = true;
                $quote  = $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] === "\\") {
                $v = substr($v, 0, -1) . $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] !== "\\") {
                $string = false;
            } else {
                $v .= $ch;
            }
        }

        return $return;
    }
}

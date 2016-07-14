<?php
/*
 * This file is part of the HessianPHP package.
 * (c) 2004-2010 Manuel G�mez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Hessian2Writer {
    /**
     * 有符号32位整数最大值
     * 
     * @var integer
     */
    const SIGNED_INT32_MAX = 2147483647;
    
    /**
     * 有符号32位整数最小值
     * 
     * @var integer
     */
    const SIGNED_INT32_MIN = -2147483648;
    
    var $refmap;
    var $typemap;
    var $logMsg = array();
    var $options;
    var $filterContainer;

    /**
     *
     * @param string $options            
     */
    function __construct($options = null) {
        $this->refmap = new HessianReferenceMap();
        $this->typemap = new HessianTypeMap();
        $this->options = $options;
    }

    /**
     *
     * @param unknown $msg            
     */
    function logMsg($msg) {
        $this->log [] = $msg;
    }

    /**
     * @param unknown $typemap
     */
    function setTypeMap($typemap) {
        $this->typemap = $typemap;
    }

    /**
     * @param unknown $container
     */
    function setFilters($container) {
        $this->filterContainer = $container;
    }

    /**
     * @param unknown $value
     * @return unknown
     */
    function writeValue($value) {
        $type = gettype($value);
        $dispatch = $this->resolveDispatch2($value);
        if (is_object($value)) {
            $filter = $this->filterContainer->getCallback($value);
            if ($filter) {
                $value = $this->filterContainer->doCallback($filter, array(
                    $value,
                    $this,
                ));
                if ($value instanceof HessianStreamResult) {
                    return $value->stream;
                }
                $dispatch = $this->resolveDispatch2($value);
            }
        }
        $data = $this->$dispatch($value);
        return $data;
    }

    /**
     * @param unknown $value
     * @return string
     */
    function resolveDispatch2($value) {
        $dispatch = '';
        switch (true) {
            case is_integer($value) :
                $dispatch = 'writeInt';
                break;
            case is_bool($value) :
                $dispatch = 'writeBool';
                break;
            case is_string($value) :
                $dispatch = 'writeString';
                break;
            case is_double($value) :
                $dispatch = 'writeDouble';
                break;
            case is_array($value) :
                $dispatch = 'writeArray';
                break;
            case is_object($value) :
                $dispatch = 'writeObject';
                break;
            case is_null($value) :
                $dispatch = 'writeNull';
                break;
            case is_resource($value) :
                $dispatch = 'writeResource';
                break;
        }
        $this->logMsg("dispatch $dispatch");
        return $dispatch;
    }

    /**
     * @param unknown $type
     * @throws Exception
     * @return string
     */
    function resolveDispatch($type) {
        $dispatch = '';
        // TODO usar algun type helper
        switch ($type) {
            case 'integer' :
                $dispatch = 'writeInt';
                break;
            case 'boolean' :
                $dispatch = 'writeBool';
                break;
            case 'string' :
                $dispatch = 'writeString';
                break;
            case 'double' :
                $dispatch = 'writeDouble';
                break;
            case 'array' :
                $dispatch = 'writeArray';
                break;
            case 'object' :
                $dispatch = 'writeObject';
                break;
            case 'NULL' :
                $dispatch = 'writeNull';
                break;
            case 'resource' :
                $dispatch = 'writeResource';
                break;
            default :
                throw new Exception("Handler for type $type not implemented");
        }
        $this->logMsg("dispatch $dispatch");
        return $dispatch;
    }

    /**
     * @return string
     */
    function writeNull() {
        return 'N';
    }

    /**
     * @param unknown $array
     * @return string|unknown
     */
    function writeArray($array) {
        if (empty($array)) {
            return 'N';
        }
        
        $refindex = $this->refmap->getReference($array);
        if ($refindex !== false) {
            return $this->writeReference($refindex);
        }
        
        /*
         * ::= x57 value* 'Z' # variable-length untyped list
         * ::= x58 int value* # fixed-length untyped list
         * ::= [x78-7f] value* # fixed-length untyped list
         */
        
        $total = count($array);
        if (HessianUtils::isListFormula($array)) {
            $this->refmap->objectlist [] = &$array;
            $stream = '';
            if ($total <= 7) {
                $len = $total + 0x78;
                $stream = pack('c', $len);
            } else {
                $stream = pack('c', 0x58);
                $stream .= $this->writeInt($total);
            }
            foreach ($array as $key => $value) {
                $stream .= $this->writeValue($value);
            }
            return $stream;
        } else {
            return $this->writeMap($array);
        }
    }

    /**
     * @param unknown $map
     * @param string $type
     * @return string
     */
    function writeMap($map, $type = '') {
        if (empty($map)) {
            return 'N';
        }
            
        /*
         * ::= 'M' type (value value)* 'Z' # key, value map pairs
         * ::= 'H' (value value)* 'Z' # untyped key, value
         */
        
        $refindex = $this->refmap->getReference($map);
        if ($refindex !== false) {
            return $this->writeReference($refindex);
        }
        
        $this->refmap->objectlist [] = &$map;
        
        if ($type == '') {
            $stream = 'H';
        } else {
            $stream = 'M';
            $stream .= $this->writeType($type);
        }
        foreach ($map as $key => $value) {
            $stream .= $this->writeValue($key);
            $stream .= $this->writeValue($value);
        }
        $stream .= 'Z';
        return $stream;
    }

    /**
     * @param unknown $value
     * @return Ambigous <string, unknown>
     */
    function writeObjectData($value) {
        $stream = '';
        $class = get_class($value);
        $index = $this->refmap->getClassIndex($class);
        
        if ($index === false) {
            $classdef = new HessianClassDef();
            $classdef->type = $class;
            if ($class == 'stdClass') {
                $classdef->props = array_keys(get_object_vars($value));
            } else {
                $classdef->props = array_keys(get_class_vars($class));
            }
            $index = $this->refmap->addClassDef($classdef);
            $total = count($classdef->props);
            
            $type = $this->typemap->getRemoteType($class);
            $class = $type ? $type : $class;
            
            $stream .= 'C';
            $stream .= $this->writeString($class);
            $stream .= $this->writeInt($total);
            foreach ($classdef->props as $name) {
                $stream .= $this->writeString($name);
            }
        }
        
        if ($index < 16) {
            $stream .= pack('c', $index + 0x60);
        } else {
            $stream .= 'O';
            $stream .= $this->writeInt($index);
        }
        
        $this->refmap->objectlist [] = $value;
        $classdef = $this->refmap->classlist [$index];
        foreach ($classdef->props as $key) {
            $val = $value->$key;
            $stream .= $this->writeValue($val);
        }
        
        return $stream;
    }

    /**
     * @param unknown $value
     * @return string|Ambigous
     */
    function writeObject($value) {
        // if($this->dateAdapter->isDatetime($value))
        // return $this->writeDate($value);
        $refindex = $this->refmap->getReference($value);
        if ($refindex !== false) {
            return $this->writeReference($refindex);
        }
        return $this->writeObjectData($value);
    }

    /**
     * @param unknown $type
     * @return string|Ambigous <string, unknown>
     */
    function writeType($type) {
        $this->logMsg("writeType $type");
        $refindex = $this->refmap->getTypeIndex($type);
        if ($refindex !== false) {
            return $this->writeInt($refindex);
        }
        $this->references->typelist [] = $type;
        return $this->writeString($type);
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeReference($value) {
        $this->logMsg("writeReference $value");
        $stream = pack('c', 0x51);
        $stream .= $this->writeInt($value);
        return $stream;
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeDate($value) {
        // $ts = $this->dateAdapter->toTimestamp($value);
        $ts = $value;
        $this->logMsg("writeDate $ts");
        $stream = '';
        if ($ts % 60 != 0) {
            $stream = pack('c', 0x4a);
            $ts = $ts * 1000;
            $res = $ts / HessianUtils::pow32;
            $stream .= pack('N', $res);
            $stream .= pack('N', $ts);
        } else { // compact date, only minutes
            $ts = intval($ts / 60);
            $stream = pack('c', 0x4b);
            $stream .= pack('c', ($ts >> 24));
            $stream .= pack('c', ($ts >> 16));
            $stream .= pack('c', ($ts >> 8));
            $stream .= pack('c', $ts);
        }
        return $stream;
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeBool($value) {
        if ($value) {
            return 'T';
        } else {
            return 'F';
        }
    }

    /**
     * @param unknown $value
     * @param unknown $min
     * @param unknown $max
     * @return boolean
     */
    function between($value, $min, $max) {
        return $min <= $value && $value <= $max;
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeInt($value) {
        if ($this->between($value, - 16, 47)) {
            return pack('c', $value + 0x90);
        } else if ($this->between($value, - 2048, 2047)) {
            $b0 = 0xc8 + ($value >> 8);
            $stream = pack('c', $b0);
            $stream .= pack('c', $value);
            return $stream;
        } elseif ($this->between($value, - 262144, 262143)) {
            $b0 = 0xd4 + ($value >> 16);
            $b1 = $value >> 8;
            $stream = pack('c', $b0);
            $stream .= pack('c', $b1);
            $stream .= pack('c', $value);
            return $stream;
        } elseif ($this->between($value, self::SIGNED_INT32_MIN, self::SIGNED_INT32_MAX)) {
            $stream = 'I';
            $stream .= pack('c', ($value >> 24));
            $stream .= pack('c', ($value >> 16));
            $stream .= pack('c', ($value >> 8));
            $stream .= pack('c', $value);
            return $stream;
        } else {
            $stream = 'L';
            $stream .= pack('c', ($value >> 56));
            $stream .= pack('c', ($value >> 48));
            $stream .= pack('c', ($value >> 40));
            $stream .= pack('c', ($value >> 32));
            $stream .= pack('c', ($value >> 24));
            $stream .= pack('c', ($value >> 16));
            $stream .= pack('c', ($value >> 8));
            $stream .= pack('c', $value);
            return $stream;
        }
    }

    /**
     * @param unknown $value
     * @return string|Ambigous <string, unknown>
     */
    function writeString($value) {
        $len = HessianUtils::stringLength($value);
        if ($len < 32) {
            return pack('C', $len) . $this->writeStringData($value);
        } else if ($len < 1024) {
            $b0 = 0x30 + ($len >> 8);
            $stream = pack('C', $b0);
            $stream .= pack('C', $len);
            return $stream . $this->writeStringData($value);
        } else {
            // TODO :chunks
            $total = $len;
            $stream = '';
            $tag = 'S';
            $stream .= $tag . pack('n', $len);
            $stream .= $this->writeStringData($value);
            return $stream;
        }
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeSmallString($value) {
        $len = HessianUtils::stringLength($value);
        if ($len < 32) {
            return pack('C', $len) . $this->writeStringData($value);
        } else if ($len < 1024) {
            $b0 = 0x30 + ($len >> 8);
            $stream .= pack('C', $b0);
            $stream .= pack('C', $len);
            return $stream . $this->writeStringData($value);
        }
    }

    /**
     * @param unknown $string
     * @return Ambigous <unknown, string>
     */
    function writeStringData($string) {
        return HessianUtils::writeUTF8($string);
    }

    /**
     * @param unknown $value
     * @return string
     */
    function writeDouble($value) {
        $frac = abs($value) - floor(abs($value));
        if ($value == 0.0) {
            return pack('c', 0x5b);
        }
        if ($value == 1.0) {
            return pack('c', 0x5c);
        }
        
        // Issue 10, Fix thanks to nesnnaho...@googlemail.com,
        if ($frac == 0 && $this->between($value, - 127, 128)) {
            return pack('c', 0x5d) . pack('c', $value);
        }
        if ($frac == 0 && $this->between($value, - 32768, 32767)) {
            $stream = pack('c', 0x5e);
            $stream .= HessianUtils::floatBytes($value);
            return $stream;
        }
        // TODO double 4 el del 0.001, revisar
        /* $mills = ( int ) ($value * 1000);
        
        if (0.001 * $mills == $value && $this->between($mills, self::SIGNED_INT32_MIN, self::SIGNED_INT32_MAX)) {
            $stream = pack('c', 0x5f);
            $stream .= pack('c', $mills >> 24);
            $stream .= pack('C', $mills >> 16);
            $stream .= pack('C', $mills >> 8);
            $stream .= pack('C', $mills);
            return $stream;
        } */
        // 64 bit double
        $stream = 'D';
        $stream .= HessianUtils::doubleBytes($value);
        return $stream;
    }

    /**
     * @param unknown $handle
     * @throws Exception
     * @return Ambigous <string, unknown>
     */
    function writeResource($handle) {
        $type = get_resource_type($handle);
        $stream = '';
        if ($type == 'file' || $type == 'stream') {
            while (! feof($handle)) {
                $content = fread($handle, 32768);
                $len = count(str_split($content));
                if ($len < 15) { // short binary
                    $stream .= pack('C', $len + 0x20);
                    $stream .= $content;
                } else {
                    $tag = 'b';
                    if (feof($handle)) {
                        $tag = 'B';
                    }
                    $stream .= $tag . pack('n', $len);
                    $stream .= $content;
                }
            }
            fclose($handle);
        } else {
            throw new Exception("Cannot handle resource of type '$type'");
        }
        return $stream;
    }
}
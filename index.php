<?php

class BalanceBrackets
{
    private $openBracket = '';
    private $closeBracket = '';

    private $string = null;

    private $braces = [];
    private $squareBrackets = [];
    private $figureBrackets = [];
    public $result = [];

    const NOT_DIVIDE = 'NOT_DIVIDE';
    const ERROR_BRACKETS = 'ERROR_BALANCE';
    const SUCCESS_BRACKETS = 'SUCCESS_BALANCE';

    const OPEN_BRACKET = '(';
    const CLOSE_BRACKET = ')';

    const OPEN_BRACKET_FIGURE = '{';
    const CLOSE_BRACKET_FIGURE = '}';

    const OPEN_BRACKET_SQUARE = '[';
    const CLOSE_BRACKET_SQUARE = ']';

    /**
     * @param $bracket
     * @return $this
     */
    public function setOpenBracket($bracket)
    {
        $this->openBracket = $bracket;
        return $this;
    }

    /**
     * @param $bracket
     * @return $this
     */
    public function setCloseBracket($bracket)
    {
        $this->closeBracket = $bracket;
        return $this;
    }

    /**
     * BalanceBrackets constructor.
     * @param $string
     */
    function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    private function prepareString()
    {
        $result = [];
        $stringLikeArray = $this->splitStringToArray($this->string);
        foreach ($stringLikeArray as $key => $value) {
            switch ($value) {
                case self::OPEN_BRACKET_FIGURE: {
                    $this->figureBrackets[$key] = $value;
                    break;
                }
                case self::CLOSE_BRACKET_FIGURE: {
                    $this->figureBrackets[$key] = $value;
                    break;
                }
                case self::OPEN_BRACKET_SQUARE: {
                    $this->squareBrackets[$key] = $value;
                    break;
                }
                case self::CLOSE_BRACKET_SQUARE: {
                    $this->squareBrackets[$key] = $value;
                    break;
                }
                case self::OPEN_BRACKET: {
                    $this->braces[$key] = $value;
                    break;
                }
                case self::CLOSE_BRACKET: {
                    $this->braces[$key] = $value;
                    break;
                }
            }
        }

        if (!empty($this->squareBrackets)) {
            $_result = $this->setOpenBracket(self::OPEN_BRACKET_SQUARE)
                ->setCloseBracket(self::CLOSE_BRACKET_SQUARE)
                ->divideBrackets($this->squareBrackets);
            if ($_result === self::NOT_DIVIDE) {
                return self::ERROR_BRACKETS;
            }
            $result += $_result;
        }
        if (!empty($this->figureBrackets)) {
            $_result = $this->setOpenBracket(self::OPEN_BRACKET_FIGURE)
                ->setCloseBracket(self::CLOSE_BRACKET_FIGURE)
                ->divideBrackets($this->figureBrackets);
            if ($_result === self::NOT_DIVIDE) {
                return self::ERROR_BRACKETS;
            }
            $result += $_result;
        }
        if (!empty($this->braces)) {
            $_result = $this->setOpenBracket(self::OPEN_BRACKET)
                ->setCloseBracket(self::CLOSE_BRACKET)
                ->divideBrackets($this->braces);
            if ($_result === self::NOT_DIVIDE) {
                return self::ERROR_BRACKETS;
            }
            $result += $_result;
        }

        foreach ($result as $key => $value) {
            foreach ($result as $_key => $_value) {
                if ($key !== $_key) {
                    if ($key > $_key && $key < $_value && $value > $_value) {
                        return self::ERROR_BRACKETS;
                    }
                    if ($value > $_key && $key < $_key && $value < $_value) {
                        return self::ERROR_BRACKETS;
                    }
                }
            }
        }

        return self::SUCCESS_BRACKETS;
    }

    /**
     * @param $string
     * @return array
     */
    private function splitStringToArray($string)
    {
        return str_split($string);
    }

    /**
     * @return string
     */
    public function isBalance()
    {
        $result = $this->prepareString();
        if ($result === self::ERROR_BRACKETS) {
            return self::ERROR_BRACKETS;
        }

        return self::SUCCESS_BRACKETS;
    }

    /**
     * @param $string
     * @return array|string
     */
    private function divideBrackets($string)
    {
        $openB = 0;
        $closeB = 0;
        $result = [];
        $stringLikeArrayValues = array_values($string);
        $stringLikeArrayKeys = array_keys($string);

        if (count($stringLikeArrayValues) % 2) {
            return self::NOT_DIVIDE;
        }

        foreach ($stringLikeArrayValues as $key => $value) {
            if ($value === $this->openBracket) {
                if ($stringLikeArrayValues[$key + 1] === $this->closeBracket) {
                    $result[$stringLikeArrayKeys[$key]] = $stringLikeArrayKeys[$key + 1];
                    continue;
                }
                for ($i = $key + 1, $len = count($stringLikeArrayValues); $i < $len; ++$i) {
                    if ($stringLikeArrayValues[$i] === $this->openBracket) {
                        $openB += 1;
                        continue;
                    }
                    $closeB += 1;
                    if ($closeB > $openB && $stringLikeArrayValues[$i] === $this->closeBracket) {
                        $openB = 0;
                        $closeB = 0;
                        $result[$stringLikeArrayKeys[$key]] = $stringLikeArrayKeys[$i];
                        break;
                    }
                }
            }
        }

        return $result;
    }
}

$string = readline("Input a string: ");

if ($string) {
    $testString[] = $string;
} else {
    $testString = [
        '{}[][][({})]()[]{}',//success
        '(([]){)(}({}))',//error
        '[[([{}]{}{()})],[{}]]',//success
        '()[]{}',//success
        '({)[[]]{]}',//error
        '(()[[]]{]})'//error
    ];
}

foreach ($testString as $string) {
    $balanceBrackets = new BalanceBrackets($string);

    echo PHP_EOL . $balanceBrackets->isBalance() . ' ' . $string . PHP_EOL;
}

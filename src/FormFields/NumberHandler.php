<?php

namespace DFZ\Dola\FormFields;

class NumberHandler extends AbstractHandler
{
    protected $codename = 'number';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('dola::formfields.number', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}

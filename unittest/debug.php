<?php
protected function loadFunctionFiles()
{
    if ($this->isLoadFunction && count($this->functionFiles))
    {
        foreach ($this->functionFiles as $functionFile)
        {
            include_once($functionFile);
        }
    }
}
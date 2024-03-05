<?php

class Reference
{
    private $tab1;
    private $tab2;
    private $att1;
    private $att2;

    public function __construct($tab1, $tab2, $att1, $att2)
    {
        $this->tab1 = $tab1;
        $this->tab2 = $tab2;
        $this->att1 = $att1;
        $this->att2 = $att2;
    }


    public function getTab1()
    {
        return $this->tab1;
    }

    public function setTab1($tab1): void
    {
        $this->tab1 = $tab1;
    }

    public function getTab2()
    {
        return $this->tab2;
    }

    public function setTab2($tab2): void
    {
        $this->tab2 = $tab2;
    }

    public function getAtt1()
    {
        return $this->att1;
    }

    public function setAtt1($att1): void
    {
        $this->att1 = $att1;
    }

    public function getAtt2()
    {
        return $this->att2;
    }

    public function setAtt2($att2): void
    {
        $this->att2 = $att2;
    }



}
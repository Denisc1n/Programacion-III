<?php
    class ClassOne{


        
        public function ToJSON(){
            return json_encode($this);
        }

        public function ToCSV()
        {
            return $this->nombre.";".$this->apellido.";".$this->dni.";".$this->legajo.";".$this->photoId.";".PHP_EOL;
        }
    }

?>
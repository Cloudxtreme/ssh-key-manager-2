<?php
class ShellScript {
    private $type;
    private $script;
    public function __construct($type) {
        $this->type = $type;
        
    }
    private function generate() {
        switch ($this->type) {
            case 'addUser':
                $this->script = <<<EOF
                    #!/bin/bash
                    
EOF;
                break;
        }
    }
}
?>

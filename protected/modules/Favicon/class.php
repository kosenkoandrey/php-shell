<?
class Favicon {

    public function Init() {
        APP::Module('Routing')->Add($this->conf['route'], 'Favicon', 'Out');
    }

    public function Out() {
        $im = imagecreatefrompng($this->conf['source']);
        header('Content-Type: image/png');
        imagepng($im);
	imagedestroy($im);
    }

}
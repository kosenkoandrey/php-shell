<?
class RobotsTXT {

    public function Init() {
        APP::Module('Routing')->Add($this->conf['route'], 'RobotsTXT', 'Out');
    }

    public function Out() {
        APP::Render('robotstxt/index');
    }

}
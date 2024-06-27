<?php
 
namespace App\View\Components\Office;
 
use Illuminate\View\Component;
use Illuminate\View\View;
 
class TableRow extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public string $label,
        public string|null $value,
        public bool $border = true,
        public bool $yesNo = false,
    ) {}
 
    public function render(): View
    {
        return view("components.office.table-row");
    }
    
    public function hasBorder()
    {
        return $this->border;
    }
    
    public function yesNo()
    {
        return $this->yesNo;
    }
}
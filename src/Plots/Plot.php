<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function __construct()
    {
        parent::__construct();
        $this->x_label = "x-label";
        $this->y_label = "y-label";
        $this->function = function ($x) { return $x; };
        $this->start = 0;
        $this->end = 10;
    }

    public function draw($canvas)
    {
        // Build convenience variables for graph measures
        $width = $this->width;
        $height = $this->height;
        $padding = 50;
        list($x_shift, $y_shift) = [
            isset($this->y_label) ? 1 : 0,
            isset($this->x_label) ? 1 : 0,
        ];
        list($graph_start_x, $graph_start_y, $graph_end_x, $graph_end_y) = [
            (1 + $x_shift)*$padding,
            imagesy($canvas) - (1 + $y_shift)*$padding,
            imagesx($canvas) - $padding,
            $padding
        ];
        list($graph_width, $graph_height) = [
            imagesx($canvas) - (2 + $x_shift)*$padding,
            imagesy($canvas) - (2 + $y_shift)*$padding
        ];

        // Create axes
        $black = imagecolorallocate($canvas, 0, 0, 0);
        imagerectangle($canvas, $graph_start_x, $graph_end_y, $graph_end_x, $graph_start_y, $black);

        // Define input function and function domain
        $function = $this->function;
        $start    = $this->start;
        $end      = $this->end;

        // Calculate graph step size and function step size
        $n             = 1000;
        $graph_step_x  = $graph_width/$n;
        $graph_step_y  = $graph_height/$n;
        $function_step = ($end - $start)/$n;

        // Calculate function values, min, max, and function scale
        $image = [];
        for ($i = 0; $i <= $n; $i++) {
            $image[] = $function($start + $i*$function_step);
        }
        $min = min($image);
        $max = max($image);
        $function_scale = $graph_height/($max - $min);

        // Draw y-axis values, dashes
        $fontpath = realpath('.'); //replace . with a different directory if needed
        putenv('GDFONTPATH='.$fontpath);
        $count = 9;
        $font = 'arial.ttf';
        $size = 10;
        $angle = 0;
        $length1 = 1;
        $length2 = 5;
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $style = array_merge(array_fill(0, $length1, $black), array_fill(0, $length2, $white));
        imagesetstyle($canvas, $style);
        for ($i = 0; $i <= $count; $i++) {
            imagettftext($canvas, $size, $angle, $graph_start_x - $padding*0.75, $size*0.5 + $graph_start_y - $i*($graph_height/$count), $black, $font, round(($min + $i*($max - $min)/$count), 1));
            if ($i !== 0 and $i !== $count) {
                imageline($canvas, $graph_start_x, $graph_start_y - $i*($graph_height/$count), $graph_end_x, $graph_start_y - $i*($graph_height/$count), IMG_COLOR_STYLED);
            }
        }

        // Draw x-axis values, dashes

        // Draw title, x-axis title, y-axis title

        // Draw graph

        return $canvas;
    }
}

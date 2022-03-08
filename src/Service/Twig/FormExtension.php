<?php

namespace App\Service\Twig;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
          new TwigFunction(
              'render_form',
              [$this, 'renderForm'],
              [
                 'needs_environment' => true,
                 'is_safe' => ['html']
              ]
          )
        ];
    }

    public function renderForm(Environment $environment, string $form, int $direction = 1){
        if ($form === '-'){
            return '-';
        }

        $position = 1;
        $sizes = [18, 14, 12, 10, 8];
        if ($direction == -1){
            $position = 2;
            $sizes = [8, 10, 12, 14, 18];
        }

        if ($direction == 1){
            $form = strrev($form);
        }

        $parts = str_split($form);
        $colors = array();
        foreach ($parts as $part){
            if ($part === 'L'){
                $colors[] = '#6E0000FF';
            }
            if ($part === 'W'){
                $colors[] = '#20A734FF';
            }
            if ($part === 'D'){
                $colors[] = '#D58600FF';
            }
        }
        return $environment->render(
          'dashboard/form.twig',
          [
              'parts' => $parts,
              'colors' => $colors,
              'sizes' => $sizes,
              'position' => $position,
          ]
        );
    }
}

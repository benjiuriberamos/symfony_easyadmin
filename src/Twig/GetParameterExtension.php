<?php

namespace App\Twig;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetParameterExtension extends AbstractExtension
{
    protected ParameterBagInterface $parameter;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_parameter', [$this, 'parameter'])
        ];
    }

    public function parameter($var, $default = '')
    {
        try {
            return $this->parameter->get($var);
        } catch (Exception $exception) {
            return $default;
        }
    }
}

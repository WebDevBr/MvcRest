<?php

namespace WebDevBr\DesignPatters;

interface SingletonInterface
{
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance();
}
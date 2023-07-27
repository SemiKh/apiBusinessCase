<?php

namespace App\Entity;

interface SlugInterface
{

    public function setSlug(string $slug): self;
    public function getNom(): ?string;

}

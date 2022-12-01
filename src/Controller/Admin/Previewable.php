<?php

namespace App\Controller\Admin;

interface Previewable
{

    public function generatePreviewUrl($entity): string;

}
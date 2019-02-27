<?php

namespace App\Collections;

use App\Photo;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PhotoCollection extends Collection implements Responsable
{
    /**
     * Merge the current collection with new photos from the specified path.
     *
     * @param string $path Folder to load the photos from.
     * @return void
     */
    public function fromPath(string $root)
    {
        return $this->merge(
            collect(scandir($root))
                ->reject(function (string $path) : bool {
                    return Str::startsWith($path, '.');
                })
                ->map(function (string $path) use ($root) : Photo {
                    return new Photo(Str::finish($root, '/') . $path);
                })
                ->reject(function (Photo $photo) : bool {
                    return $photo->date->isFuture();
                })
        );
    }

    /**
     * Get an HTTP response containing the photo.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param  \Illuminate\Http\Request  $request Current HTTP request.
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if ($this->isEmpty()) {
            abort(404);
        }

        return $this->random()->toResponse($request);
    }
}

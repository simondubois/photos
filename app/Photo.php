<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Intervention\Image\ImageManager;
use MarcW\RssWriter\Extension\Core\Enclosure;
use MarcW\RssWriter\Extension\Core\Guid;
use MarcW\RssWriter\Extension\Core\Item;

class Photo implements Responsable
{
    /**
     * Create a new Photo instancce from file.
     *
     * @param string     $path RPath relative to the disk root.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->name = pathinfo($path, PATHINFO_FILENAME);
        [$this->year, $this->month, $this->day] = array_map('intval', explode('-', $this->name));
        $this->date = Carbon::createFromDate($this->year, $this->month, $this->day)->startOfDay();
        $this->title = $this->date->formatLocalized('%A %e %B %Y');
        $this->url = url($this->year . '/' . $this->month . '/' . $this->day);
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
        $image = with(new ImageManager())->make($this->path);

        if ($request->width || $request->height) {
            $image->resize($request->width, $request->height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($request->grayscale) {
            $image->greyscale();
        }

        $image->interlace();

        return response($image->encode('jpg', 50), 200, [
            'Cache-control' => 'private, max-age=604800',
            'Content-type' => 'image/jpeg',
        ]);
    }

    /**
     * Get the RSS representation of the photo.
     *
     * @return MarcW\RssWriter\Extension\Core\Item
     */
    public function toRss() : Item
    {
        return with(new Item())
            ->setTitle($this->title)
            ->setLink(url())
            ->setDescription('<img src="' . $this->url . '">')
            ->setPubDate($this->date)
            ->setGuid((new Guid())->setGuid($this->date));
    }
}

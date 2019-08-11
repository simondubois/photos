<?php

namespace App\Collections;

use App\Photo;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MarcW\RssWriter\Bridge\Symfony\HttpFoundation\RssStreamedResponse;
use MarcW\RssWriter\Extension\Atom\AtomLink;
use MarcW\RssWriter\Extension\Atom\AtomWriter;
use MarcW\RssWriter\Extension\Core\Channel;
use MarcW\RssWriter\Extension\Core\CoreWriter;
use MarcW\RssWriter\Extension\Core\Image;
use MarcW\RssWriter\RssWriter;

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
     * Get the HTTP response for one of the random photo in the collection.
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

    /**
     * Get an HTTP response representing the collection as a RSS feed.
     *
     * @return \Illuminate\Http\Response
     */
    public function toRss()
    {
        $rssWriter = new RssWriter(null, [], true);
        $rssWriter->registerWriter(new AtomWriter());
        $rssWriter->registerWriter(new CoreWriter());

        $channel = new Channel();
        $channel->setTitle(env('APP_NAME'));
        $channel->setLink(url());
        $channel->setLastBuildDate(optional($this->last())->date);
        $channel->addExtension(
            with(new AtomLink())->setRel('self')->setHref(url('feed'))->setType('application/rss+xml')
        );

        $channel->setImage(
            with(new Image())
                ->setTitle(env('APP_NAME'))
                ->setUrl(url('logo-large.png'))
                ->setLink(url())
        );

        $this
            ->reverse()
            ->take(env('FEED_LENGTH'))
            ->map(function (Photo $photo) {
                return $photo->toRss();
            })
            ->each([$channel, 'addItem']);

        return new RssStreamedResponse($channel, $rssWriter);
    }
}

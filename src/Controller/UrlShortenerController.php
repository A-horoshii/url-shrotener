<?php
namespace AHoroshii\UrlShortenerBundle\Controller;

use AHoroshii\UrlShortenerBundle\Entity\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlShortenerController extends AbstractController
{
    /**
     * @param string $hash
     * @param Request $request
     * @return RedirectResponse
     */
    public function shortUrlRedirectAction($hash, Request $request)
    {
        $redirectResponse =  $this->get('horoshii_url_shortener.service.url_shortener')->getRedirectResponse($hash, $request);
        if ($redirectResponse instanceof RedirectResponse) {
            return $redirectResponse;
        } else {
            throw new NotFoundHttpException("Page not found");
        }
    }


    /**
     * @param Request $request
     */
    public function shortUrlCreateAction(Request $request)
    {
        $params = [];
        if ($request->isMethod('post')) {
            $url = $request->request->get('url');
            $ttl= $request->get('ttl');
            $shortUrl =  $this->get('horoshii_url_shortener.service.url_shortener')->createShortUrl($url, $ttl);
            if ($shortUrl instanceof ShortUrl) {
                $params['shortUrl'] = $this->generateUrl(
                    'AHoroshii_short_url_redirect',
                    ['hash' => $shortUrl->getHash()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            } else {
                $params['errors'] = $shortUrl;
            }
        }
        $this->render('@UrlShortener/ShortUrl/short_url_create.html.twig', $params);
    }


    /**
     * @param Request $request
     */
    public function shortUrlListAction(Request $request)
    {
        $queryParams = $request->query->all();
        if (!isset($queryParams['limit'])) {
            $queryParams['limit'] = 200;
        }
        $shortUrlsRepository = $this->getDoctrine()->getRepository(ShortUrl::class);
        $shortUrls = $shortUrlsRepository->getShortUrls($queryParams);
        $shortUrlQuantity = $shortUrlsRepository->getShortUrlsCount($queryParams);
        $this->render('', ['shortUrls'=>$shortUrls, 'shortUrlQuantity'=>$shortUrlQuantity]);
    }
}
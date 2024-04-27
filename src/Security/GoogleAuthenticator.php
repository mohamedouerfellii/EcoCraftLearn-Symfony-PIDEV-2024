<?php


namespace App\Security;

use App\Entity\Users; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                // /** @var FacebookUser $facebookUser */
                $googleUser = $client->fetchUserFromToken($accessToken);

                $email = $googleUser->getEmail();

                // // 1) have they logged in with Facebook before? Easy!
                // $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['facebookId' => $googleUser->getId()]);

                // if ($existingUser) {
                //     return $existingUser;
                // }

                // 2) do we have a matching user by email?
                $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
                if (!$user) {
                    $user = new Users();
                    $user->setEmail($email);
                    // Optionally, set other user properties based on Google OAuth data
                     $user->setFirstName($googleUser->getFirstName());
                     $user->setLastName($googleUser->getLastName());
                     $user->setImage($googleUser->getAvatar());
                     $user->setPassword("wqeqweqw");
                    $user->setGender("autre");
                     $user->setRole("other");
                    $user->setNumtel(123);
                   
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                }
                // 3) Maybe you just want to "register" them by creating
                // a User object
                // $user->setFacebookId($googleUser->getId());
                // $this->entityManager->persist($user);
                // $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $targetUrl = $this->router->generate('admin_dashboard');
            return new RedirectResponse($targetUrl);
        }
        else if(in_array('ROLE_STUDENT', $user->getRoles(), true)) {
            $targetUrl = $this->router->generate('home_page');
            return new RedirectResponse($targetUrl);
        }
       else if (in_array('ROLE_TEACHER', $user->getRoles(), true)) {
            $targetUrl = $this->router->generate('tutor_course_dashboard');
            return new RedirectResponse($targetUrl);
        }
        

        // or, on success, let the request continue to be handled by the controller
        $targetUrl = $this->router->generate('google_user');
        return new RedirectResponse($targetUrl);;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        // return new Response($message, Response::HTTP_FORBIDDEN);
        // $this->addFlash('warning', 'email not associated with zeroWaste account');
        $request->getSession()->getFlashBag()->add('warning', 'email not associated with ecoCraft account');
        $targetUrl = $this->router->generate('app_register');
        return new RedirectResponse($targetUrl);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}

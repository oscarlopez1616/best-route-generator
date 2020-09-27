<?php
declare(strict_types=1);

namespace BestRouteGenerator\Tests\AcceptanceTest\BestRouteGenerator\Infrastructure\Ui\Controller;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetTheShortestPathControllerTest extends KernelTestCase
{
    /**
     * @test
     */
    public function itShouldPrintRoute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('zinio:best-route-generator:get-shortest-path');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString(
            "Beijing\nVladivostok\nTokyo\nBangkok\nSingapore\nPerth\nMelbourne\nAuckland\nSan Francisco\nVancouver\nAnchorage\nToronto\nNew York\nCaracas\nSan Jose\nMexico City\nLima\nRio\nSantiago\nDakar\nAccra\nCasablanca\nParis\nLondon\nPrague\nMoscow\nAstana\nNew Delhi\nJerusalem\nCairo\nLusaka\nReykjavík\n",
            $output
        );
    }


}

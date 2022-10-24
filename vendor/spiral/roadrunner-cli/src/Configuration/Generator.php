<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration;

use Spiral\RoadRunner\Console\Configuration\Section\Rpc;
use Spiral\RoadRunner\Console\Configuration\Section\SectionInterface;
use Spiral\RoadRunner\Console\Configuration\Section\Version;
use Symfony\Component\Yaml\Yaml;

class Generator
{
    /** @var SectionInterface[] */
    protected array $sections = [];

    /** @psalm-var non-empty-array<class-string<SectionInterface>> */
    protected const REQUIRED_SECTIONS = [
        Version::class,
        Rpc::class,
    ];

    public function generate(Plugins $plugins): string
    {
        $this->collectSections($plugins->getPlugins());

        return $this->getHeaderComment() . PHP_EOL . Yaml::dump($this->getContent(), 10);
    }

    protected function getContent(): array
    {
        $content = [];
        foreach ($this->sections as $section) {
            $content += $section->render();
        }

        return $content;
    }

    protected function collectSections(array $plugins): void
    {
        $sections = \array_merge(self::REQUIRED_SECTIONS, $plugins);

        foreach ($sections as $section) {
            $this->fromSection(new $section());
        }
    }

    /** @psalm-return non-empty-array<SectionInterface> */
    protected function fromSection(SectionInterface $section): void
    {
        if (!isset($this->sections[\get_class($section)])) {
            $this->sections[\get_class($section)] = $section;
        }

        foreach ($section->getRequired() as $required) {
            $this->fromSection(new $required());
        }
    }

    protected function getHeaderComment(): string
    {
        $comment = [
            '########################################################################################',
            '#                       THIS IS SAMPLE OF THE CONFIGURATION                            #',
            '#           IT\'S NOT A DEFAULT CONFIGURATION, IT\'S JUST A SIMPLE SAMPLE                #',
            '#       MORE DOCS CAN BE FOUND HERE: <https://roadrunner.dev/docs/intro-config>        #',
            '########################################################################################',
            '',
            '# Hint: RR will replace any config options using reference to environment variables,',
            '# eg.: `option_key: ${ENVIRONMENT_VARIABLE_NAME}`.',
            '',
            '# Important: TCP port numbers for each plugin (rpc, http, etc) must be unique!',
            ''
        ];

        return \implode(PHP_EOL, $comment);
    }
}

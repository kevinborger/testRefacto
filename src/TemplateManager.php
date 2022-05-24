<?php

class TemplateManager
{
    protected ?User $user;
    protected ?Quote $quote;
    protected ?Site $site;
    protected ?Destination $destination;
    protected array $replaces;


    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $APPLICATION_CONTEXT = ApplicationContext::getInstance();
        $this->user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
        if($quote) {
            $this->quote = QuoteRepository::getInstance()->getById($quote->id);
            $this->site = SiteRepository::getInstance()->getById($quote->siteId);
            $this->destination = DestinationRepository::getInstance()->getById($quote->destinationId);
        }
        $this->replaces = ReplaceRepository::getInstance()->getAll();

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject);
        $replaced->content = $this->computeText($replaced->content);

        return $replaced;
    }

    private function computeText($text)
    {
        /**
         * @var Replace $replace
         */
        foreach($this->replaces as $replace) {
            //TODO
        }

        if ($this->quote)
        {
            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary     = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[quote:summary_html]',
                        Quote::renderHtml($this->quote),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[quote:summary]',
                        Quote::renderText($this->quote),
                        $text
                    );
                }
            }

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]',$this->destination->countryName,$text);
        }

        if ($this->destination)
            $text = str_replace('[quote:destination_link]', $this->site->url . '/' . $this->destination->countryName . '/quote/' . $this->quote->id, $text);
        else
            $text = str_replace('[quote:destination_link]', '', $text);

        if($this->user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($this->user->firstname)), $text);
        }

        return $text;
    }
}

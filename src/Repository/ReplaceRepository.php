<?php
class ReplaceRepository
{
    use SingletonTrait;

    /**
     * @return array
     */
    public function getAll()
    {
        $replaces = [];

        $functions = [
            ["quote", "destination_link"],
            ["quote", "summary_html"],
            ["quote", "summary"],
            ["quote", "destination_name"],
            ["user", "first_name"]
        ];

        foreach($functions as $function) {
            $replaces[] = new Replace(...$function);
        }

        return $replaces;
    }
}

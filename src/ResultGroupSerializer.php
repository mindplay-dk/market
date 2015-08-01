<?php

namespace mindplay\market;

class ResultGroupSerializer
{
    /**
     * @param ResultGroup $group
     *
     * @return string JSON dump
     */
    public function toJSON(ResultGroup $group)
    {
        $target = $group->target;

        $data = array(
            'target'  => array(
                'package_name' => $target->package_name,
                'version'      => $target->version,
                'flavor'       => $target->flavor,
            ),
            'results' => array_map(
                function (Result $result) {
                    return array(
                        'reference' => $result->test->reference,
                        'flavor'    => $result->test->flavor,
                        'input'     => $result->test->input,
                        'expected'  => $result->test->expected,
                        'output'    => $result->output,
                        'success'   => $result->success,
                        'exact'     => $result->exact,
                    );
                },
                $group->results
            ),
        );

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

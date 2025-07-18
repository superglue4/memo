<?php
namespace Memo;

class HelloWorld
{
    public function say(\Memo\Sjr4746 $sjr4746, \Memo\Sjr4746 $sjr47462)
    {

        // Sjr4746 클래스의 say 메서드를 호출
        $message = $sjr4746->say();
        $message .= $sjr47462->say();
        echo $message;
    }
}
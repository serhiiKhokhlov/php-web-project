<?php
 
namespace Application;
 
readonly class UserData
{
    public function __construct(
        public int $id,
        public string $userName
    ) {
    }
}
 


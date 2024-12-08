<?php

namespace App\Enums;

// دا ثابت بيعرفني الرساله بتاعتي حالتها ايه دلوقتي هل لسه متخزنه ولا اتبعتت ولا حصل مشكله فيها
enum EmailStatus: int {
  case Queue = 0;
  case Sent = 1;
  case Failed = 2;

  public function toString(): string {
    return match($this) {
      self::Queue => "In Queue",
      self::Sent => "Sent",
      self::Failed => "Failed"
    };
  }
}
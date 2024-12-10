<?php

declare (strict_types = 1);

namespace App\Models;

use App\Enums\EmailStatus;
use App\Model;
use Symfony\Component\Mime\Address;

// بعمل كلاس فيها فانكشن بتخزن الرساله بتاعتي في الداتا بيز عشان ارجع واستخدمها بعدين
// وي بورث المودل عشان هو الي فيه الداتا بيز بتاعتي
class Email extends Model
{
    // الفانكشن ديه عشان تستلم البيانات بتاعت الاميل وتخزنها في الداتا بيز
    public function queue(
        Address $to,
        Address $from,
        string $subject,
        string $html,
        ?string $text = null
    ): void {
        $stmt = $this->db->prepare(
            'INSERT INTO emails (subject, status, html_body, text_body, meta, created_at)
              VALUES (?, ?, ?, ?, ?, NOW())'
        );

        $meta['to'] = $to->toString();
        $meta['from'] = $from->toString();

        $stmt->executeStatement([$subject, EmailStatus::Queue->value, $html, $text, json_encode($meta)]);
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        $stmt = $this->db->prepare(
            'SELECT *
            FROM emails
            WHERE status = ?'
        );
        return $stmt->executeQuery([$status->value])->fetchAllAssociative();
    }

    public function markEmailSent(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE email
        SET status = ?, sent_at = NOW()
        WHERE id = ?'
        );

        $stmt->executeStatement([EmailStatus::Sent->value, $id]);
    }
}

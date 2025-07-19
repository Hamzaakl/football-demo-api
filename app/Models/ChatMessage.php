<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'user_id',
        'message',
        'user_name',
        'user_avatar',
        'is_system',
        'metadata',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Maç
     */
    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    /**
     * Kullanıcı
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sistem mesajlarını getir
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Kullanıcı mesajlarını getir
     */
    public function scopeUser($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Son mesajları getir
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Belirli maçın mesajlarını getir
     */
    public function scopeByFixture($query, $fixtureId)
    {
        return $query->where('fixture_id', $fixtureId);
    }

    /**
     * Mesaj zamanını formatla
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Tam tarih formatı
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d.m.Y H:i');
    }
}

<?php

namespace App\Livewire\User;

use Livewire\Component;

class MyTestimonial extends Component
{
    public $message = '';
    public $rating = 5;
    public $testimonialId = null;

    public function mount()
    {
        $testimonial = \App\Models\Testimonial::where('user_id', auth()->id())->first();
        if ($testimonial) {
            $this->testimonialId = $testimonial->id;
            $this->message = $testimonial->message;
            $this->rating = $testimonial->rating;
        }
    }

    public function save()
    {
        $this->validate([
            'message' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'message.required' => 'O campo depoimento é obrigatório.',
            'message.min' => 'O depoimento deve ter pelo menos 10 caracteres.',
            'message.max' => 'O depoimento deve ter no máximo 500 caracteres.',
            'rating.required' => 'A avaliação é obrigatória.',
        ]);

        \App\Models\Testimonial::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'message' => $this->message,
                'rating' => $this->rating,
                'is_approved' => false, // Reset approval if edited
            ]
        );

        session()->flash('message', 'Depoimento salvo com sucesso! Ele será analisado pela nossa equipe.');
    }

    public function render()
    {
        return view('livewire.user.my-testimonial');
    }
}

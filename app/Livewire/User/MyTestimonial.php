<?php

namespace App\Livewire\User;

use Livewire\Component;

class MyTestimonial extends Component
{
    public $message = '';
    public $rating = 5;
    public $profession = '';
    public $showProfession = false;
    public $testimonialId = null;

    public function mount()
    {
        $this->profession = auth()->user()->profession;
        $testimonial = \App\Models\Testimonial::where('user_id', auth()->id())->first();
        if ($testimonial) {
            $this->testimonialId = $testimonial->id;
            $this->message = $testimonial->message;
            $this->rating = $testimonial->rating;
            $this->showProfession = $testimonial->show_profession;
        }
    }

    public function save()
    {
        $this->validate([
            'message' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'profession' => 'nullable|string|max:100',
            'showProfession' => 'boolean',
        ], [
            'message.required' => 'O campo depoimento é obrigatório.',
            'message.min' => 'O depoimento deve ter pelo menos 10 caracteres.',
            'message.max' => 'O depoimento deve ter no máximo 500 caracteres.',
            'rating.required' => 'A avaliação é obrigatória.',
        ]);

        // Update user profession
        auth()->user()->update(['profession' => $this->profession]);

        \App\Models\Testimonial::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'message' => $this->message,
                'rating' => $this->rating,
                'show_profession' => $this->showProfession,
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

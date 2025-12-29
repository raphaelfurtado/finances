<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ManageTestimonials extends Component
{
    use \Livewire\WithPagination;

    public function toggleApproval($id)
    {
        $testimonial = \App\Models\Testimonial::findOrFail($id);
        $testimonial->is_approved = !$testimonial->is_approved;
        $testimonial->save();
        $this->dispatch('testimonial-updated', 'Status de aprovação atualizado.');
    }

    public function toggleAvatar($id)
    {
        $testimonial = \App\Models\Testimonial::findOrFail($id);
        $testimonial->show_avatar = !$testimonial->show_avatar;
        $testimonial->save();
        $this->dispatch('testimonial-updated', 'Exibição de avatar atualizada.');
    }

    public function delete($id)
    {
        \App\Models\Testimonial::destroy($id);
        $this->dispatch('testimonial-deleted', 'Depoimento excluído.');
    }

    public function render()
    {
        return view('livewire.admin.manage-testimonials', [
            'testimonials' => \App\Models\Testimonial::with('user')->latest()->paginate(10),
        ]);
    }
}

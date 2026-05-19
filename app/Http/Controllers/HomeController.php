<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function welcome(): View
    {
        return view('welcome', [
            'quotes' => [
                [
                    'name' => 'Epictetus',
                    'role' => 'Stoic Philosopher',
                    'greek' => 'Anechou kai apechou.',
                    'translation' => 'Belajarlah bertahan, dan belajarlah menahan diri.',
                    'source' => 'Fragments',
                    'image' => 'images/quotes/epictetus.jpg',
                    'accent' => 'bg-[#FFD93D]',
                ],
                [
                    'name' => 'Marcus Aurelius',
                    'role' => 'Roman Emperor, Stoic Thinker',
                    'greek' => 'Hoti en oligistois keitai to eudaimonos biosai.',
                    'translation' => 'Hidup yang baik bergantung pada sedikit hal.',
                    'source' => 'Meditations 7:68',
                    'image' => 'images/quotes/marcus-aurelius.jpeg',
                    'accent' => 'bg-[#C4B5FD]',
                ],
                [
                    'name' => 'Socrates',
                    'role' => 'Athenian Philosopher',
                    'greek' => 'Tas men poleis anathemasi, tas de psychas mathesmasi dei kosmein.',
                    'translation' => 'Kota bisa dihias dengan monumen, tapi jiwa harus dibentuk dengan pelajaran.',
                    'source' => 'Quoted in Greek tradition',
                    'image' => 'images/quotes/socrates.jpg',
                    'accent' => 'bg-[#FF6B6B]',
                ],
            ],
        ]);
    }
}

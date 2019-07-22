<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon;
use App\Models\Helpfaq;

use App\Sosadfun\Traits\FAQObjectTraits;


class FAQController extends Controller
{
    use FAQObjectTraits;

    public function __construct()
    {
        $this->middleware('admin')->except('index');
    }

    public function index()
    {
        $faqs = $this->find_faqs();
        return view('FAQs.index', compact('faqs'));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|min:1|max:6',
        ]);
        $keys = explode('-',$request->key);

        return view('FAQs.create', compact('keys'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|min:1|max:6',
            'question' => 'required|string|min:1|max:180',
            'answer'=>'required|string|min:1|max:2000',
        ]);
        Helpfaq::create($request->only('key','question','answer'));
        $this->clear_all_faqs();
        return redirect()->route('help')->with('success','成功添加FAQ条目');
    }

    public function edit(Helpfaq $faq)
    {
        $keys = explode('-',$faq->key);
        return view('FAQs.edit', compact('faq','keys'));
    }

    public function update(Helpfaq $faq, Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string|min:1|max:180',
            'answer'=>'required|string|min:1|max:2000',
        ]);
        $faq->update($request->only('question','answer'));
        $this->clear_all_faqs();
        return redirect()->route('help')->with('success','成功修改FAQ条目');
    }

}

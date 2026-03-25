<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Database\QueryException;

class ContactController extends Controller
{
    private function corsHeaders(): array
    {
        // Frontend uses `fetch` without credentials; allow all origins to prevent CORS/preflight failures.
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
            'Access-Control-Allow-Credentials' => 'false',
        ];
    }

    public function index()
    {
        return response()->json(['message' => 'Contact API is running.'])->withHeaders($this->corsHeaders());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'phone'   => 'nullable|string|max:20',
                'message' => 'required|string',
            ]);

            Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully! 🎉',
            ], 201)->withHeaders($this->corsHeaders());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422)->withHeaders($this->corsHeaders());
        } catch (QueryException $e) {
            \Log::error('Contact form DB error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database is temporarily unavailable. Please try again in a moment.',
            ], 503)->withHeaders($this->corsHeaders());
        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while submitting the form.',
            ], 500)->withHeaders($this->corsHeaders());
        }
    }
}
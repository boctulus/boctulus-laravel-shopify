<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;

/*
    Prueba con GraphQL
*/
class ShopiTestController extends Controller
{   
    public function apirest_products()
    {
        $config = [
            'ShopUrl' => 'xxxxxxxx.myshopify.com',
            'AccessToken' => 'xxxxxxxx', // Reemplaza por tu Access Token real
            'ApiVersion' => '2024-01'
        ];
    
        $shopify = new ShopifySDK($config);
    
        try {
            // Obtener productos
            $products = $shopify->Product->get();
            return $products;
        } catch (\Exception $e) {
            return "Error fetching products: " . $e->getMessage();
        }
    }

    public function graphql_products()
    {
        $shopifyStoreUrl = 'xxxxxxxx'; // env('SHOPIFY_STORE_URL');
        $shopifyApiKeyAdmin = 'xxxxxxxxxx'; env('SHOPIFY_API_KEY_ADMIN');
        $shopifyApiVersion = '2024-01'; // env('SHOPIFY_API_VERSION', '2024-01');

        if (!$shopifyStoreUrl || !$shopifyApiKeyAdmin) {
            Log::error('Shopify credentials are missing');
            return response()->json([
                'success' => false,
                'error' => 'Shopify credentials are not configured',
            ], 500);
        }

        $shopifyUrl = "https://{$shopifyStoreUrl}";

        $query = <<<GRAPHQL
        {
            products(first: 10) {
                edges {
                    node {
                        id
                        title
                        handle
                        description
                        priceRange {
                            minVariantPrice {
                                amount
                                currencyCode
                            }
                        }
                        images(first: 1) {
                            edges {
                                node {
                                    originalSrc
                                    altText
                                }
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        try {
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $shopifyApiKeyAdmin,
                'Content-Type' => 'application/json',
            ])->post("{$shopifyUrl}/admin/api/{$shopifyApiVersion}/graphql.json", [
                'query' => $query,
            ]);

            if ($response->failed()) {
                Log::error('Shopify API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $response->json(),
                ], $response->status());
            }

            $products = $response->json('data.products.edges');
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Shopify products', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred',
            ], 500);
        }
    }
}

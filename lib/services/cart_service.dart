import 'dart:convert';
import 'firebase_service.dart';
import '../models/product.dart';
import '../models/cart_item.dart';
import '../models/auth_token.dart';

class CartService extends FirebaseService {
  CartService([AuthToken? authToken]) : super(authToken);

  Future<List<CartItem>> fetchCartItems() async {
    try {
      final cartItemsMap = await httpFetch('$databaseUrl/cart.json?auth=$token')
          as Map<String, dynamic>?;

      final List<CartItem> cartItems = [];

      cartItemsMap?.forEach((cartItemId, cartItem) {
        cartItems.add(
          CartItem(
            id: cartItemId,
            title: cartItem['title'],
            imageUrl: cartItem['imageUrl'],
            price: cartItem['price'],
            quantity: cartItem['quantity'],
          ),
        );
      });

      return cartItems;
    } catch (error) {
      print(error);
      return [];
    }
  }

  Future<void> addCartItem(Product product) async {
    try {
      await httpFetch(
        '$databaseUrl/cart.json?auth=$token',
        method: HttpMethod.post,
        body: jsonEncode({
          'title': product.title,
          'imageUrl': product.imageUrl,
          'price': product.price,
          'quantity': 1,
        }),
      );
    } catch (error) {
      print(error);
    }
  }

  Future<void> addToCart(Product product, int quantity) async {
    try {
      await httpFetch(
        '$databaseUrl/cart.json?auth=$token',
        method: HttpMethod.post,
        body: jsonEncode({
          'title': product.title,
          'imageUrl': product.imageUrl,
          'price': product.price,
          'quantity': quantity,
        }),
      );
    } catch (error) {
      print(error);
    }
  }

  Future<void> updateCartItem(CartItem cartItem) async {
    try {
      await httpFetch(
        '$databaseUrl/cart/${cartItem.id}.json?auth=$token',
        method: HttpMethod.put,
        body: jsonEncode({
          'title': cartItem.title,
          'imageUrl': cartItem.imageUrl,
          'price': cartItem.price,
          'quantity': cartItem.quantity,
        }),
      );
    } catch (error) {
      print(error);
    }
  }

  Future<void> deleteCartItem(String cartItemId) async {
    try {
      await httpFetch(
        '$databaseUrl/cart/$cartItemId.json?auth=$token',
        method: HttpMethod.delete,
      );
    } catch (error) {
      print(error);
    }
  }

  Future<void> clearCartItems() async {
    try {
      await httpFetch('$databaseUrl/cart.json?auth=$token',
          method: HttpMethod.delete);
    } catch (error) {
      print(error);
    }
  }
}

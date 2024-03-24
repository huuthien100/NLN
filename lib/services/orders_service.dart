import 'dart:convert';
import 'firebase_service.dart';
import '../models/order_item.dart';
import '../models/auth_token.dart';

class OrdersService extends FirebaseService {
  OrdersService([AuthToken? authToken]) : super(authToken);

  Future<List<OrderItem>> fetchOrders() async {
    final List<OrderItem> orders = [];

    try {
      final ordersMap = await httpFetch('$databaseUrl/orders.json?auth=$token')
          as Map<String, dynamic>?;

      ordersMap?.forEach((orderId, order) {
        orders.add(
          OrderItem.fromJson({
            'id': orderId,
            ...order,
          }),
        );
      });

      return orders;
    } catch (error) {
      print(error);
      return orders;
    }
  }

  Future<OrderItem?> addOrder(OrderItem order, double total) async {
    try {
      final newOrder = await httpFetch(
        '$databaseUrl/orders.json?auth=$token',
        method: HttpMethod.post,
        body: jsonEncode(order.toJson()),
      ) as Map<String, dynamic>?;

      return order.copyWith(
        id: newOrder!['name'],
      );
    } catch (error) {
      print(error);
      return null;
    }
  }

  streamOrders() {}
}

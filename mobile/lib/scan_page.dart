
import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'billet_details_page.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class ScanPage extends StatefulWidget {
  const ScanPage({super.key});

  @override
  State<ScanPage> createState() => _ScanPageState();
}

class _ScanPageState extends State<ScanPage> {
  bool isScanning = true;

  Future<void> fetchBilletDetails(String qrCode) async {
    try {
      final response = await http.post(
        Uri.parse('http://192.168.169.123/cinephoria/api/details_billet.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'reservation_id': int.parse(qrCode)}),
      );

      final data = jsonDecode(response.body);
      if (data['success']) {
        setState(() => isScanning = false);
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BilletDetailsPage(details: data['details']),
          ),
        );
      } else {
        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            title: const Text('Erreur'),
            content: Text(data['message']),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.pop(context);
                  setState(() => isScanning = true);
                },
                child: const Text('OK'),
              ),
            ],
          ),
        );
      }
    } catch (e) {
      showDialog(
        context: context,
        builder: (_) => AlertDialog(
          title: const Text('Erreur de connexion'),
          content: const Text('Impossible de contacter le serveur.'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
                setState(() => isScanning = true);
              },
              child: const Text('OK'),
            ),
          ],
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Scanner un billet')),
      body: MobileScanner(
        onDetect: (BarcodeCapture capture) {
          if (isScanning && capture.barcodes.isNotEmpty) {
            final barcode = capture.barcodes.first;
            final rawValue = barcode.rawValue;

            if (rawValue != null) {
              fetchBilletDetails(rawValue);
            }
          }
        },
      ),
    );
  }
}

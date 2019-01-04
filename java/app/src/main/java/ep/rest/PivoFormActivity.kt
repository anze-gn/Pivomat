package ep.rest

import android.content.Intent
import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.util.Log
import kotlinx.android.synthetic.main.activity_pivo_form.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException

class PivoFormActivity : AppCompatActivity(), Callback<Void> {

    private var pivo: Pivo? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_pivo_form)

        btnSave.setOnClickListener {
            val author = etAuthor.text.toString().trim()
            val title = etTitle.text.toString().trim()
            val description = etDescription.text.toString().trim()
            val price = etPrice.text.toString().trim().toDouble()
            val year = etYear.text.toString().trim().toInt()

//            if (pivo == null) { // dodajanje
//                PivoService.instance.insert(author, title, price,
//                        year, description).enqueue(this)
//            } else { // urejanje
//                PivoService.instance.update(pivo!!.id, author, title, price,
//                        year, description).enqueue(this)
//            }
        }

        val pivo = intent?.getSerializableExtra("ep.rest.pivo") as Pivo?
        if (pivo != null) {
            etAuthor.setText(pivo.imeZnamke)
            etTitle.setText(pivo.naziv)
            etPrice.setText(pivo.cena.toString())
            etYear.setText(pivo.alkohol.toString())
            etDescription.setText(pivo.opis)
            this.pivo = pivo
        }
    }

    override fun onResponse(call: Call<Void>, response: Response<Void>) {
        val headers = response.headers()

        if (response.isSuccessful) {
            val id = if (pivo == null) {
                // Preberemo Location iz zaglavja
                Log.i(TAG, "Insertion completed.")
                val parts = headers.get("Location").split("/".toRegex()).dropLastWhile { it.isEmpty() }.toTypedArray()
                // spremenljivka id dobi vrednost, ki jo vrne zadnji izraz v bloku
                parts[parts.size - 1].toInt()
            } else {
                Log.i(TAG, "Editing saved.")
                // spremenljivka id dobi vrednost, ki jo vrne zadnji izraz v bloku
                pivo!!.id
            }

            val intent = Intent(this, PivoDetailActivity::class.java)
            intent.putExtra("ep.rest.id", id)
            startActivity(intent)
        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Log.e(TAG, errorMessage)
        }
    }

    override fun onFailure(call: Call<Void>, t: Throwable) {
        Log.w(TAG, "Error: ${t.message}", t)
    }

    companion object {
        private val TAG = PivoFormActivity::class.java.canonicalName
    }
}

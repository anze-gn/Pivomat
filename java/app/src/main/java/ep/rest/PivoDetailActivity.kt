package ep.rest

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.util.Log
import kotlinx.android.synthetic.main.activity_pivo_detail.*
import kotlinx.android.synthetic.main.content_pivo_detail.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import retrofit2.http.Field
import java.io.IOException

class PivoDetailActivity : AppCompatActivity(), Callback<Pivo> {

    private var pivo: Pivo? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_pivo_detail)
        setSupportActionBar(toolbar)

//        fabEdit.setOnClickListener {
//            val intent = Intent(this, PivoFormActivity::class.java)
//            intent.putExtra("ep.rest.pivo", pivo)
//            startActivity(intent)
//        }
//

        val app = application as PivomatApp

        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        val id = intent.getIntExtra("ep.rest.id", 0)

        if (id > 0) {
            app.instance.get(id).enqueue(this)
        }






    }

    private fun deleteBook() {
        // todo
    }

    override fun onBackPressed() {
        finish()
    }

    override fun onResponse(call: Call<Pivo>, response: Response<Pivo>) {
        pivo = response.body()

        Log.i(TAG, "Got result: $pivo")

        if (response.isSuccessful) {
            tvOpis.text = "Znamka: " + pivo?.imeZnamke + "\n" + "Stil: " + pivo?.imeStila + "\n" + "Alkoholna vsebnost: " + pivo?.alkohol + "%\n" + "Neto količina: " + pivo?.kolicina + "l\n\n" + pivo?.opis +"\n\n" + "Cena: " + pivo?.cena + "€\n"
            toolbarLayout.title = pivo?.naziv

            fabVkosarico.setOnClickListener {
                val api = CartService.instance

                api.insert(pivo?.id!!, 1, pivo?.cena!! , pivo?.naziv!!).enqueue(object: Callback<String> {
                    override fun onResponse(call: Call<String>?, response: Response<String>?) {

                    }

                    override fun onFailure(call: Call<String>?, t: Throwable?) {

                    }

                })
            }

        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Log.e(TAG, errorMessage)
            tvOpis.text = errorMessage
        }
    }

    override fun onFailure(call: Call<Pivo>, t: Throwable) {
        Log.w(TAG, "Error: ${t.message}", t)
    }

    companion object {
        private val TAG = PivoDetailActivity::class.java.canonicalName
    }
}


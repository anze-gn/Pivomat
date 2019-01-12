package ep.rest


import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.util.TypedValue
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_kosarica.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException
import java.util.*

class KosaricaActivity : AppCompatActivity(), Callback<List<CartItem>> {

    private var adapter: CartAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kosarica)
        val app = application as PivomatApp
        adapter = CartAdapter(this)
        items.adapter = adapter

        btnKoncajNakup.setOnClickListener {
            CartService.instance.posljiNarocilo(app.cookie!!).enqueue(object : Callback<Void> {
                override fun onFailure(call: Call<Void>?, t: Throwable?) {
                    Log.i("KOSARICA", "Nuspesno zakljucen nakup in poslano narocilo")
                    tvStatusNarocila.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18.0F)
                    tvStatusNarocila.text = "Nespešno poslano naročilo, prosim poskusite ponovno!"
                }

                override fun onResponse(call: Call<Void>?, response: Response<Void>?) {

                    if (response!!.isSuccessful) {
                        tvStatusNarocila.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18.0F)
                        tvStatusNarocila.text = "Naročilo uspešno poslano!"
                        Log.i("KOSARICA", "Nakup uspesno zakljucen, narocilo poslano")
                        Thread.sleep(2000) //Da se vidi napis uspesnega nakupa
                        finish();
                        overridePendingTransition(0, 0);
                        startActivity(intent);
                        overridePendingTransition(0, 0);
                    } else {
                        Log.i("KOSARICA", "Nespešno poslano naročilo, prosim poskusite ponovno!")
                        tvStatusNarocila.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18.0F)
                        tvStatusNarocila.text = "Nespešno poslano naročilo, prosim poskusite ponovno!"
                        Thread.sleep(2000) //Da se vidi napis uspesnega nakupa
                        finish();
                        overridePendingTransition(0, 0);
                        startActivity(intent);
                        overridePendingTransition(0, 0);
                    }

                }


            })
        }



        container.setOnRefreshListener { CartService.instance.getAll(app.cookie!!).enqueue(this) }


        CartService.instance.getAll(app.cookie!!).enqueue(this)
    }


    override fun onResponse(call: Call<List<CartItem>>?, response: Response<List<CartItem>>?) {
        val hits = response!!.body()

        if (response.isSuccessful) {
            Log.i("KOSARICA", "Uspesno prikazana kosarica")
            adapter?.clear()
            adapter?.addAll(hits)
            val sCena = koncnaCena(hits)
            val app = application as PivomatApp
            app.skupnaCena = sCena
            tvSkupnaCena.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18.0F)
            tvSkupnaCena.text = String.format(Locale.ENGLISH, "Skupna cena: %.2f€", sCena)
            container.isRefreshing = false

        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show()

        }
    }

    override fun onFailure(call: Call<List<CartItem>>?, t: Throwable?) {
        Log.i("KOSARICA", "Nemorem prikazati kosarice")
        container.isRefreshing = false
    }

    fun koncnaCena(list: List<CartItem>): Double {
        var vsota = 0.0
        for (e in list) {
            vsota += (e.cena * e.kol)
        }
        return vsota
    }
}

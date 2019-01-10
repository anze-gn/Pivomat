package ep.rest

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.util.Log
import android.widget.AdapterView
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_main.*
import okhttp3.Cookie
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException



class MainActivity : AppCompatActivity(), Callback<List<Pivo>> {

    private var adapter: PivoAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        adapter = PivoAdapter(this)
        items.adapter = adapter
        items.onItemClickListener = AdapterView.OnItemClickListener { _, _, i, _ ->
            val pivo = adapter?.getItem(i)
            if (pivo != null) {
                val intent = Intent(this, PivoDetailNeprijavljenActivity::class.java)
                intent.putExtra("ep.rest.id", pivo.id)
                startActivity(intent)
            }
        }


        val app = application as PivomatApp


        container.setOnRefreshListener { app.instance.getAll().enqueue(this) }

        btnPrijava.setOnClickListener {
            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
        }

        app.instance.getAll().enqueue(this)
    }

    override fun onResponse(call: Call<List<Pivo>>, response: Response<List<Pivo>>) {
        val hits = response.body()
        //val cookie = response.headers().get("Set-Cookie")
//
//       val app = application as PivomatApp
//        app.cookie = null
////                response.headers().get("Set-Cookie")
//
//        val cookie = Cookie.Builder().name("")

        if (response.isSuccessful) {
            Log.i(TAG, "Hits: " + hits.size)
            adapter?.clear()
            adapter?.addAll(hits)
        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show()
            Log.e(TAG, errorMessage)
        }
        container.isRefreshing = false
    }

    override fun onFailure(call: Call<List<Pivo>>, t: Throwable) {
        Log.w(TAG, "Error: ${t.message}", t)
        container.isRefreshing = false
    }

    companion object {
        private val TAG = MainActivity::class.java.canonicalName
    }
}

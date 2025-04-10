using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.Repositories;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.EntityFramework;

public class EfVehicleModelDal:GenericRepository<VehicleModel>,IVehicleModel
{
    public EfVehicleModelDal(Context context) : base(context)
    {
        
    }
}
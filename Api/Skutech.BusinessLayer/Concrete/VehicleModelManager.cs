using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;
using IVehicleModel = Skutech.DataAccessLayer.Abstract.IVehicleModel;

namespace Skutech.BusinessLayer.Concrete;

public class VehicleModelManager : IGenericService<VehicleModel>
{
    private readonly IVehicleModel _vehicleModel;

    public VehicleModelManager(IVehicleModel vehicleModel)
    {
        _vehicleModel = vehicleModel;
    }

    public void TInsert(VehicleModel entity)
    {
        _vehicleModel.Insert(entity);
    }

    public void TUpdate(VehicleModel entity)
    {
        _vehicleModel.Update(entity);
    }

    public void TDelete(VehicleModel entity)
    {
        _vehicleModel.Delete(entity);
    }

    public VehicleModel? TGetElementById(int id)
    {
        return _vehicleModel.GetElementById(id);
    }

    public List<VehicleModel> TGetList()
    {
        return _vehicleModel.GetList();
    }
}